ppSearch = {
	form: 0,
	refreshableConditions: [],
	selectOptions: {},

	val_cat: 0,
	select_topcat: 0,
	select_subcat: 0,
	
	topCategoryChangedByUser: function() {
		var catid = ppSearch.select_topcat.val()
		ppSearch.val_cat.val(catid)

		ppSearch.select_subcat.val("")
	},

	subCategoryChanged: function() {
		var subcatval = ppSearch.select_subcat.val()
		if (subcatval != "") {
			ppSearch.val_cat.val(subcatval)
		}
		else {
			var topcatval = ppSearch.select_topcat.val()
			ppSearch.val_cat.val(topcatval)
		}
	},

	refreshControls: function() {
		// Trigger the dynamic conditionals
		jQuery.each(ppSearch.refreshableConditions, function(index, condition) {
			condition()
		})
	},

	init: function() {
		ppSearch.bindSelectors()
		ppSearch.bindEvents()

		if (ppSearch.val_cat.val() == "") { // on home page, no search info
			ppSearch.topCategoryChangedByUser()
		}

		ppSearch.bindRefreshables()
		ppSearch.refreshControls()
	},

	bindSelectors: function() {
		ppSearch.form = jQuery("#search_form")
		ppSearch.val_cat = jQuery("#category_value")
		ppSearch.select_topcat = jQuery("#search_category")
		ppSearch.select_subcat = jQuery("#search_subcategory")
	},

	bindEvents: function() {
		ppSearch.form.on("change", ppSearch.refreshControls)

		ppSearch.select_topcat.change(ppSearch.topCategoryChangedByUser)
		ppSearch.select_subcat.change(ppSearch.subCategoryChanged)
	},

	bindRefreshables: function() {
		jQuery('[data-showif]', ppSearch.form).each(function() {
			var conditional_container = jQuery(this)
			var orClauses = conditional_container.data("showif").split("||")

			var clauseset = []
			jQuery.each(orClauses, function(index, clause) {
				var parts = clause.split("=")
				clauseset.push({
					selector: parts[0], 
					cond_values: parts[1].split(",")
				})
			})

			// Create the condition code
			var condition = function() {
				return function() {
					var found = false
					for (var i = 0; i < clauseset.length; i++) {
						var clause = clauseset[i]
						var value = jQuery(clause.selector).val()

						for (var j = 0; j < clause.cond_values.length; j++) {
							if (clause.cond_values[j] == value) {
								found = true
								break
							}
						}

						if (found) break
					}

					container_visible = conditional_container.is(":visible")
					if (found) {
						if (!container_visible) {
							conditional_container.show()
						}
					}
					else if(container_visible) {
						conditional_container.hide()
				        $(conditional_container).find(':input').each(function() {
				            switch(this.type) {
				                case 'password':
				                case 'select-multiple':
				                case 'select-one':
				                case 'text':
				                case 'textarea':
				                    jQuery(this).val('');
				                    break;
				                case 'checkbox':
				                case 'radio':
				                    this.checked = false;
				            }
				        });
					}
				}
			}()

			ppSearch.refreshableConditions.push(condition)
		})

		jQuery('select[data-childfilter]', ppSearch.form).each(function() {
			var select = jQuery(this)
			var orClauses = select.data("childfilter").split("||")

			// take a backup of the options
			ppSearch.selectOptions[select.attr("id")] = jQuery("option", select)

			var conditions = []
			jQuery.each(orClauses, function(index, clause) {
				var parts = clause.split("=")
				var selector = parts[0]
				var valueSelectorStr = parts[1]

				var valueSelectorParts = valueSelectorStr.split("->")

				if (valueSelectorParts[0] != "?") 
					return

				var valueSelector = function(el) {
					return el.val()
				}

				if (valueSelectorParts.length > 1) { // Make func to pipe el value through
					var mapfunc = eval(valueSelectorParts[1]) 

					// To support pipping through multiple functions you would make a recursive method to create chained closures
					// Not doing this since not neccessary
					var innerValueSelector = valueSelector
					var valueSelector = function() {
						return function(el) {
							return mapfunc(innerValueSelector(el))
						}
					}()
				}

				conditions.push({
					selector: selector, 
					valueSelector: valueSelector
				})
			})

			var condition = function() { return function() {
				if (!select.is(":visible")) return

				var activeCondition
				jQuery.each(conditions, function(index, condition) {
					if (jQuery(condition.selector, ppSearch.form).is(":visible")) {
						activeCondition = condition
					}
				})

				var conditionEl = jQuery(activeCondition.selector, ppSearch.form)
				var conditionValue = activeCondition.valueSelector(conditionEl)
				var container = select.parents("div").eq(0)

				if (activeCondition && select.data("prevactive") != activeCondition.selector + "-" + conditionValue) {
					// Weirdness in this section due to iOS Safari compatibility (restoring value, removing options instead of hiding)
					var selectedVal = jQuery('option:selected', select).val()

					select.empty()

					var newOptions = ppSearch.selectOptions[select.attr("id")].filter(".showalways, [data-parent='"+conditionValue+"']")
					select.append(newOptions)

					var newSelectedEl = newOptions.filter('[value="'+selectedVal+'"]')

					if (newSelectedEl) {
						newSelectedEl.prop('selected', true)
					}
					else {
						select.val("")
					}

					select.data("prevactive", activeCondition.selector + "-" + conditionValue)
				}
			}}()

			ppSearch.refreshableConditions.push(condition)
		})
	}
}

function mapPetCategoryIDToPetType(catID) {
	if (catID == 309) return "dog"
	if (catID == 310) return "cat"
	if (catID == 311) return "bird"
	if (catID == 312) return "fish"
	if (catID == 313) return "reptile"
	if (catID == 314) return "other"
}
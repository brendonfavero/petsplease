/*
	Refreshables by Chris
	Takes rules defined on elements inside the context object (assumably a form), generating rules to
	listen defined by data-* attributes on elements.

	= showif =
	e.g. given element with data-showif="#selectel=bob", then element will only show if $("#selectel").val() == "bob"
	Can be in the form of "selector=val[,orVal2,orVal3,...][||selector=...]",
	where val's can be a comma seperated list and multiple selectors can be defined with "||" which are OR'ed

	= childfilter =
	e.g. given select list with data-childfilter="#el=?", then this elements <option>'s will be only be shown by
	their data-parent attribute when it matches the value of #el
	The child value ("?") can be pipped into a function before comparison: data-childfilter="#selector=?->pippingFunction"

	Usage: $(formWithDataRuleChildren).refreshables()
*/
(function($) {
	var $form;
	var refreshableConditions = [];
	var selectOptions = {};

	var bindRefreshables = function() {
		$('[data-showif]', $form).each(function() {
			var conditional_container = $(this)
			var orClauses = conditional_container.data("showif").split("||")

			var clauseset = []
			$.each(orClauses, function(index, clause) {
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
						var value = $(clause.selector).val()

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
				                    $(this).val('');
				                    break;
				                case 'checkbox':
				                case 'radio':
				                    this.checked = false;
				            }
				        });
					}
				}
			}()

			refreshableConditions.push(condition)
		})

		$('select[data-childfilter]', $form).each(function() {
			var select = $(this)
			var orClauses = select.data("childfilter").split("||")

			// take a backup of the options
			selectOptions[select.attr("id")] = $("option", select)

			var conditions = []
			$.each(orClauses, function(index, clause) {
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
				$.each(conditions, function(index, condition) {
					if ($(condition.selector, $form).is(":visible")) {
						activeCondition = condition
					}
				})

				var conditionEl = $(activeCondition.selector, $form)
				var conditionValue = activeCondition.valueSelector(conditionEl)
				var container = select.parents("div").eq(0)

				if (activeCondition && select.data("prevactive") != activeCondition.selector + "-" + conditionValue) {
					// Weirdness in this section due to iOS Safari compatibility (restoring value, removing options instead of hiding)
					var selectedVal = $('option:selected', select).val()

					select.empty()

					var newOptions = selectOptions[select.attr("id")].filter(".showalways, [data-parent='"+conditionValue+"']")
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

			refreshableConditions.push(condition)
		})
	}

	var refreshControls = function() {
		// Trigger the dynamic conditionals
		$.each(refreshableConditions, function(index, condition) {
			condition()
		})
	}

	$.fn.refreshables = function() {
		$form = this
		bindRefreshables()
		$form.on("change", refreshControls)
		refreshControls() // do a refresh straight away
	}
}(jQuery));

/* 
	BindChainValues (by Chris)
	Given a list of selects (jquery objects) get the deepest possible value and .val it on the binded input.
	e.g. select1 has a value and select2 has a value, select2's value will be used
	e.g. select1 has a value but select2 has no value (select2.val()==""), select1's value will be used

	Usage: $(inputToHoldValue).bindChainValues([select1,select2,select3,...])  
*/
(function($) {
	$.fn.bindChainValues = function(selects) {
		var $finalField = this

		$.each(selects, function(index, el) {
			var above = index - 1 >= 0 ? selects[index - 1] : null
			var belows = index + 1 < selects.length ? selects.slice(index + 1) : null

			$(el).on('change', function() {
				var this_value = $(this).val()

				if (this_value != "") {
					$finalField.val(this_value)
				}
				else if (above) {
					$finalField.val(above.val())
				}
				else {
					$finalField.val("")
				}

				!belows || belows.each(function() { $(this).val("") })
			})
		})

		// if $finalField has no value set it to the current value of the highest select
		if ($finalField.val() == "") {
			var init_value = selects[0].val()
			$finalField.val(init_value)
		}
	}
}(jQuery));

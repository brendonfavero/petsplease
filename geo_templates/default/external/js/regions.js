//6.0.7-3-gce41f93

//TODO: Convert this to use a JS object like geoRegions = {} like rest of built-in JS functions

var parent = [];
var countries = [];
var regionNames = [];

Event.observe(window, 'load', function () {
	initRegions();
});

function initRegions()
{
	for (var i = 0; i < regionNames.length; i++) {
		var regionElemName = regionNames[i][0];
		var subRegionElemName = regionNames[i][1];
		var country_dd = $(regionElemName);
		var state_dd = $(subRegionElemName);
		if (country_dd) {
			country_dd.observe('change', function (event) {
				var elem = Event.element(event);
				chooseCountry(elem);
			});
			
			if ( state_dd && country_dd.selectedIndex > 0 ) {
				var selected = state_dd.options[state_dd.selectedIndex].value;
				chooseCountry( country_dd, subRegionElemName, selected );
			} else {
				state_dd.disabled = true;
			}
		}
	}
}

function addCountry ( country_id, country_index )
{
	countries[country_index] = country_id;
}

function addRegion( parent_id, value, text )
{
	var new_array = new Array();

	if (parent[parent_id] == undefined) {
		parent[parent_id] = new Array();
	}
	new_array[0] = value;
	new_array[1] = text;
	parent[parent_id].push( new_array );
}

function populateState(state_element_id, parent_id, selected )
{
	//parent_id = htmlentities(parent_id);
	//alert( parent_id + ' - '+state_element_id );
	dd = $(state_element_id);
	dd.update( new Element('option',{'value': 'none'}).update(selectAStateText) );
	
	for( var x=0; parent[parent_id] && x < parent[parent_id].length; x++ ) {
		//insert option for state.
		dd.insert(new Element('option',{'value': parent[parent_id][x][0]}).update(parent[parent_id][x][1]));
	}
	if (selected) {
		//select the state
		dd.value = selected;
	}
	dd.disabled = false;
}
function chooseCountry(country_id, next_dd, selected )
{
	if (next_dd) {
		var states = $(next_dd);
	} else {
		var states = null;
		for (var i = 0; i < regionNames.length && !states; i++) {
			if (regionNames[i][0] == country_id.identify()) {
				next_dd = regionNames[i][1];
				states = $(next_dd);
			}
		}
		if (!states) {
			//error getting states field.
			return;
		}
	}
	if (country_id.getValue() != 'none') {
		states.disabled = false;
		populateState(next_dd, country_id.getValue(), selected );
	} else {
		states.disabled = true;
	}
}


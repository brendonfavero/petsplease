// JavaScript Document
var ampse = { cache: {}, util: {}, globals: {} }; // init ampse admin object

ampse.generateCache = function( arrayOfIds ){
	$J.each( arrayOfIds, 
		function( i,v ) {
			ampse.cache[v] = $J('#'+v);						  
		});
};


{if $topcategory eq 308} {* Pets for Sale *}
	{if $subcategory eq 309} {* Dogs for Sale *}
		{include file="petsplease_category_templates/pets_for_sale.tpl"}
	{elseif $subcategory eq 310} {* Cats for Sale *}
		{include file="petsplease_category_templates/pets_for_sale.tpl"}
	{else}
		{include file="petsplease_category_templates/pets_for_sale.tpl"}
	{/if}
{elseif $topcategory eq 315} {* Pet Products *}
	Need custom template for Pets Products
{elseif $topcategory eq 316} {* Breeders *}
	Need custom template for Pet Breeders
{elseif $topcategory eq 318} {* Services *}
	Need custom template for Pet Services
{elseif $topcategory eq 319} {* Clubs *}
	Need custom template for Pet Clubs
{elseif $topcategory eq 411} {* Accomodation *}
	Need custom template for Pet Accomodation
{else}
	No template specified for toplevel category ({$topcategory})
{/if}

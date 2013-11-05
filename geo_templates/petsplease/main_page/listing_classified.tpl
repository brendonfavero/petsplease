{if $topcategory eq 308} {* Pets for Sale *}
	{if $subcategory eq 309} {* Dogs for Sale *}
		{include file="petsplease_category_templates/pets_for_sale.tpl"}
	{elseif $subcategory eq 310} {* Cats for Sale *}
		{include file="petsplease_category_templates/pets_for_sale.tpl"}
	{else}
		{include file="petsplease_category_templates/pets_for_sale.tpl"}
	{/if}
{elseif $topcategory eq 315} {* Pet Products *}
	{include file="petsplease_category_templates/pets_for_sale.tpl"}
{elseif $topcategory eq 316} {* Breeders *}
	{include file="petsplease_category_templates/pets_for_sale.tpl"}
{elseif $topcategory eq 318} {* Services *}
	{include file="petsplease_category_templates/pets_for_sale.tpl"}
{elseif $topcategory eq 319} {* Clubs *}
	{include file="petsplease_category_templates/pets_for_sale.tpl"}
{elseif $topcategory eq 411} {* Accomodation *}
	{include file="petsplease_category_templates/pets_for_sale.tpl"}
{else}
	No template specified for toplevel category ({$topcategory})
{/if}

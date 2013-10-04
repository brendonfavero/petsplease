{* 6.0.7-3-gce41f93 *}
{foreach $columns as $column}
	<ul style='width:{$colWidth}%;' class='extraCheckboxes'>
		{foreach $column as $c}
			<li>{$c}</li>
		{/foreach}
	</ul>
{/foreach}
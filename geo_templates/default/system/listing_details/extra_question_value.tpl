{* 6.0.7-3-gce41f93 *}
<ul class='extraQuestionValue' id='extraQuestionValue'>
	{foreach $answers as $a}
		<li>
			{if $a.link}<a href="{$a.link}">{/if}
			{$a.value}
			{if $a.link}</a>{/if}
		</li>
	{/foreach}
</ul>
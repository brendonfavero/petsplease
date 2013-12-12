<div class="col_left">
	<div id="browsing_search">
		<form id="selector_form">
			<input type="hidden" name="a" value="ap" />
			<input type="hidden" name="addon" value="ppPetSelector" />
			<input type="hidden" name="page" value="detail" />

			<div>
				<label for="selector_pagetype">Pet Type</label>
				<select id="selector_pagetype">
					{foreach $pettypes as $pettype_id=>$pettype}
						<option value="{$pettype_id}"{if $detail.pettype_id eq $pettype_id} selected="selected"{/if}>{$pettype}</option>
					{/foreach}
				</select>
			</div>

			<div>
				<label for="selector_breed">Breed</label>
				<select id="selector_breed" name="id" data-childfilter="#selector_pagetype=?">
					{foreach $nav as $breed}
						<option value="{$breed.id}" data-parent="{$breed.pettype_id}"{if $detail.id eq $breed.id} selected="selected"{/if}>{$breed.breed}</option>
					{/foreach}
				</select>
			</div>

			<button>Show Detail</button>
		</form>

		<script> jQuery("#selector_form").refreshables() </script>
	</div>
</div>

<div class="col_right">
	{if $detail}
		<h1 class="title">
			<div style="float:right">{$pettypes[$detail.pettype_id]}</div>
			{$detail.breed}
		</h1>
		<div class="content_box_1" style="padding:15px;">
			{if $detail.description neq ""}
				<div class="field_set">
					<span class="field_name">Description</span>
					<span class="field_value">{$detail.description}</span>
				</div>
			{/if}

			{if $detail.height neq ""}
				<div class="field_set">
					<span class="field_name">Height</span>
					<span class="field_value">{$detail.height}</span>
				</div>
			{/if}

			{if $detail.weight neq ""}
				<div class="field_set">
					<span class="field_name">Weight</span>
					<span class="field_value">{$detail.weight}</span>
				</div>
			{/if}

			{if $detail.size neq ""}
				<div class="field_set">
					<span class="field_name">Body Size</span>
					<span class="field_value">{$detail.size}</span>
				</div>
			{/if}

			{if $detail.lifespan neq ""}
				<div class="field_set">
					<span class="field_name">Life Span</span>
					<span class="field_value">{$detail.lifespan}</span>
				</div>
			{/if}

			<div class="field_set">
				<span class="field_name">Hypoallergenic</span>
				<span class="field_value">
				{if $detail.hypoallergenic eq 1}Yes{else}No{/if}
				</span>
			</div>

			{if $detail.colours neq ""}
				<div class="field_set">
					<span class="field_name">Colours</span>
					<span class="field_value">{$detail.colours}</span>
				</div>
			{/if}

			{if $detail.coatlength neq ""}
				<div class="field_set">
					<span class="field_name">Coat Length</span>
					<span class="field_value">{$detail.coatlength}</span>
				</div>
			{/if}

			{if $detail.housing neq ""}
				<div class="field_set">
					<span class="field_name">Housing</span>
					<span class="field_value">{$detail.housing}</span>
				</div>
			{/if}

			{if $detail.familyfriendly neq ""}
				<div class="field_set">
					<span class="field_name">Family Friendliness</span>
					<span class="field_value">
						<div class="starrating-bg" title="{$detail.familyfriendly} star/s">
							<div class="starrating" style="padding-right:{$detail.familyfriendly * 20}%"></div>
						</div>
					</span>
				</div>
			{/if}

			{if $detail.trainability neq ""}
				<div class="field_set">
					<span class="field_name">Trainability</span>
					<span class="field_value">
						<div class="starrating-bg" title="{$detail.trainability} star/s">
							<div class="starrating" style="padding-right:{$detail.trainability * 20}%"></div>
						</div>
					</span>
				</div>
			{/if}

			{if $detail.energy neq ""}
				<div class="field_set">
					<span class="field_name">Energy Level</span>
					<span class="field_value">
						<div class="starrating-bg" title="{$detail.energy} star/s">
							<div class="starrating" style="padding-right:{$detail.energy * 20}%"></div>
						</div>
					</span>
				</div>
			{/if}

			{if $detail.grooming neq ""}
				<div class="field_set">
					<span class="field_name">Grooming</span>
					<span class="field_value">
						<div class="starrating-bg" title="{$detail.grooming} star/s">
							<div class="starrating" style="padding-right:{$detail.grooming * 20}%"></div>
						</div>
					</span>
				</div>
			{/if}

			{if $detail.shedding neq ""}
				<div class="field_set">
					<span class="field_name">Hair Shedding</span>
					<span class="field_value">
						<div class="starrating-bg" title="{$detail.shedding} star/s">
							<div class="starrating" style="padding-right:{$detail.shedding * 20}%"></div>
						</div>
					</span>
				</div>
			{/if}
		</div>

	{else}
		Select a type and breed on the left to view information.
	{/if}
</div>
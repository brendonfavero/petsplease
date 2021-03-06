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
	
	{addon addon="ppAds" tag="adspot" aid=4}
</div>

<div class="col_right">	
	{if $detail}
		<a class="button" style="background: #2e3192; color: white;" href="index.php?a=19&b[subcategories_also]=1&c=309&breed={$detail.breed}">View {$detail.breed} Classifieds on Pets Please</a>
		<br/><br/>
		<h1 class="title">
			<div style="float:right">{$pettypes[$detail.pettype_id]}</div>
			{$detail.breed}
		</h1>
		<div class="content_box_1" style="padding:15px;">
			{if $detail.description neq ""}
				<div class="field_set">
					<span class="field_name">Description</span>
					<span class="field_value">{$detail.description|regex_replace:"/\r\n?|\n/":'<br>'}</span>
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
			
			{if $detail.hypoallergenic neq ""}
				<div class="field_set">
					<span class="field_name">Hypoallergenic</span>
					<span class="field_value">
					{if $detail.hypoallergenic eq 1}Yes{else}No{/if}
					</span>
				</div>
			{/if}
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
			
			{if $detail.origin neq ""}
				<div class="field_set">
					<span class="field_name">Place of Origin</span>
					<span class="field_value">{$detail.origin}</span>
				</div>
			{/if}

			{if $detail.owner_experience neq ""}
				<div class="field_set">
					<span class="field_name">Owner Experience</span>
					<span class="field_value">{$detail.owner_experience}</span>
				</div>
			{/if}

			{if $detail.loudness neq ""}
				<div class="field_set">
					<span class="field_name">Loudness</span>
					<span class="field_value">{$detail.loudness}</span>
				</div>
			{/if}

			{if $detail.companion neq ""}
				<div class="field_set">
					<span class="field_name">Companion required?</span>
					<span class="field_value">{$detail.companion}</span>
				</div>
			{/if}

			{if $detail.sexing neq ""}
				<div class="field_set">
					<span class="field_name">Sexing</span>
					<span class="field_value">{$detail.sexing}</span>
				</div>
			{/if}
			
			{if $detail.cage neq ""}
				<div class="field_set">
					<span class="field_name">Cage size</span>
					<span class="field_value">{$detail.cage}</span>
				</div>
			{/if}

			{if $detail.stimulation neq ""}
				<div class="field_set">
					<span class="field_name">Stimulation needs</span>
					<span class="field_value">{$detail.stimulation}</span>
				</div>
			{/if}

			{if $detail.time_outside neq ""}
				<div class="field_set">
					<span class="field_name">Time Outside Needed</span>
					<span class="field_value">{$detail.time_outside} Hours</span> 
				</div>
			{/if}

			{if $detail.talks neq ""}
				<div class="field_set">
					<span class="field_name">Talks?</span>
					<span class="field_value">{$detail.talks}</span>
				</div>
			{/if}
			
			{if $detail.bird_friendlyness neq ""}
				<div class="field_set">
					<span class="field_name">Friendlyness</span>
					<span class="field_value">{$detail.bird_friendlyness}</span>
				</div>
			{/if}

			{if $detail.bird_trainability neq ""}
				<div class="field_set">
					<span class="field_name">Trainability</span>
					<span class="field_value">{$detail.bird_trainability}</span>
				</div>
			{/if}

			{if $images}
				<div class="petselector_images">
					{foreach $images as $image}
						<img src="{$image.image_url}">
					{/foreach}
				</div>
			{/if}
		</div>

	{else}
		<p>The Pets Please Pet Selector lists various details about different breeds of pets to help you in your search to find the ideal pet for you on Pets Please.
		On each Pet Selector page you can find a description and information about important stats such size, life span, colour, coat length as well as ratings
		in 5 fields (family friendlyness, trainability, energy level, grooming and hair shedding) to allow you to find the best pet for you and your family.
		<br/><br/>
		Click show more detail from one of the popular breeds below or select a breed from the list on the left. Then click on View classifieds on Pets Please to
		see the current classifieds available on the Pets Please website for that breed.</p>
		<div style="width:50%; float:left">
			<img src="/addons/ppPetSelector/images/110-1387781293.jpg"/>
			<br/>
			<span class="petselectorbreed">Labrador Retriever</span>
			<br/>
			<span class="field_name">Family Friendliness</span>
			<span class="field_value">
				<div class="starrating-bg" title="5 star/s">
					<div class="starrating" style="padding-right:100%"></div>
				</div>
			</span>
			<a href="petselector?a=ap&addon=ppPetSelector&page=detail&id=110">Show More Detail</a>
		</div>
		<div style="width:50%; float:right">
			<img src="/addons/ppPetSelector/images/27-1387781202.jpg">
			<br/>
			<span class="petselectorbreed">Border Collie</span>
			<br/>
			<span class="field_name">Trainability</span>
			<span class="field_value">
				<div class="starrating-bg" title="5 star/s">
					<div class="starrating" style="padding-right:100%"></div>
				</div>
			</span>
			<a href="petselector?a=ap&addon=ppPetSelector&page=detail&id=27">Show More Detail</a>
		</div>
		<div style="width:50%; float:left; margin-top:50px; margin-bottom:50px">
			<img src="/addons/ppPetSelector/images/256-1387837605.jpg">
			<br/>
			<span class="petselectorbreed">Persian</span>
			<br/>
			<span class="field_name">Grooming</span>
			<span class="field_value">
				<div class="starrating-bg" title="5 star/s">
					<div class="starrating" style="padding-right:100%"></div>
				</div>
			</span>
			<a href="petselector?a=ap&addon=ppPetSelector&page=detail&id=256">Show More Detail</a>
		</div>
		<div style="width:50%; float:right; margin-top:50px; margin-bottom:50px">
			<img src="/addons/ppPetSelector/images/265-1387837563.jpg">
			<br/>
			<span class="petselectorbreed">Siamese</span>
			<br/>
			<span class="field_name">Energy Level</span>
			<span class="field_value">
				<div class="starrating-bg" title="5 star/s">
					<div class="starrating" style="padding-right:100%"></div>
				</div>
			</span>
			<a href="petselector?a=ap&addon=ppPetSelector&page=detail&id=265">Show More Detail</a>
		</div>
	{/if}
	
	<div style="text-align:center;font-style:italic; margin-top:25px">
		<strong style="font-weight:bold">Disclaimer:</strong> Every effort has been made to make the Site as accurate as possible. 
		You acknowledge that any reliance upon any advice, opinion, statement, advertisement, or other information displayed or distributed through the Site is at Your sole risk and We are not responsible or labile for any loss or damage that results from the use of the information on the Site. 
		We reserve the right in Our sole discretion and without notice to You to correct any errors or omissions in any portion of the Site. 
	</div>
</div>
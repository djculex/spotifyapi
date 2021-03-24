<link rel="stylesheet" type="text/css" href="<{$xoops_url}>/modules/spotifyapi/assets/css/player.css">

<div class = "spotifyholdermain owl-carousel owl-theme">
<{section name=i loop=$block}>
	<div class="spotifyholder">
		<p class="spotify-playtime">
			<{$smarty.const._SPOTIFYAPI_PLAYTIME}><{$block[i].times}>	
			</p>
		<img class="spotify-image-top" src='<{$block[i].image}>'/>
		<div class="spotify-image-body">
		<p class="spotify-artist-text">
			<{$block[i].artist}> - <{$block[i].title}>
			<p class="spotify-albumname">
				<{$block[i].album}> © <{$block[i].releaseyear}>
			</p>
			
		</p>
		</div>
	</div>
<{/section}>
</div>
<link rel="stylesheet" type="text/css" href="<{$xoops_url}>/modules/spotifyapi/assets/css/player.css">

<div class = "spotifyholdermain">
<{section name=i loop=$block}>
	<div class="spotifyholder">
		
		<p class = "spotifycounter">
			<{$smarty.section.i.iteration}> / <{$smarty.section.i.total}>
		</p>
		<p id = "spotify-playtime" class="spotify-playtime">
			<a href = "<{$block[i].playlistlink}>" target = "_blank">
				<{$smarty.const._SPOTIFYAPI_PLAYTIME}><{$block[i].times}>
			</a>
			</p>
			<a href = "<{$block[i].artistlink}>" target = "_blank">
				<img class="spotify-image-top" src='<{$block[i].image}>'/>
			</a>
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
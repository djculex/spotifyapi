<div class="table-responsive">
	<div class="mw-100 p-3 mb-2 bg-danger text-white">
		<h1 class="text-center"><{$title}></h1>
		<p class="text-center"><b><{$subtitle}></b></p>
	</div>
	<table class="table ">
		<thead class="table-dark">
			<tr>
				<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTTHISWEEK}></th>
				<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTLASTWEEK}></th>
				<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTALBUMCOVER}></th>
				<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTARTISTTITLE}></th>
				<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTALBUMNAME}></th>
				<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTRELEASEYEAR}></th>
			</tr>
		</thead>
		<tbody>
			<{section name=i loop=$chart}>
				<tr>
					<th scope="row"><h3><strong><{$chart[i].tw}></strong></h3></th>
					<td><small>
							<{if is_int($chart[i].lw)}>
								<{$chart[i].lw}>
							<{else}>
								<span class="badge badge-secondary"><{$chart[i].lw}></span>
							<{/if}>
						<small>
					</td>
					<td class="w-25">
						<img src="<{$chart[i].image}>" class="img-fluid img-thumbnail" height="100px" width="75px" alt="<{$chart[i].album}> - <{$chart[i].year}>">
					</td>

					<td alt="<{$chart[i].pop}>" ><{$chart[i].artist}> - <{$chart[i].title}></td>
					<td><a href="<{$chart[i].artlink}>"><{$chart[i].album}></a></td>
					<td><{$chart[i].year}></td>
					</tr>
			<{/section}>
		</tbody>
	</table>   
</div>
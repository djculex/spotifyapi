<div class="table-responsive">
		<h3 class="text-center">
			<strong><{$smarty.const._SPOTIFYAPI_FILTER_TITLE}></strong>
		</h3>
		
		<ul class="nav flex-column nav-link" id = "spotifyDateNav" style="display: flex;position: relative;margin: 0 auto 0 auto;text-align: center;width: fit-content;">
			<li class="nav-item">
				<a class="nav-link active" id="spotifyapidropstart" style="display: inline-block;padding: 22px 20px;">
					<{$radiobutton}>
				</a>
			</li>	
			<li class="nav-item">
				<a class="nav-link active" id="spotifyapidropstart" style="display:inline-block">
					<{$smarty.const._SPOTIFYAPI_FILTER_DATEFROM}>
					<{$dropstart}>
				</a>
			</li>
			<li class="nav-item">
			<a class="nav-link active" id="spotifyapidropend" style="display:inline-block">
				<{$smarty.const._SPOTIFYAPI_FILTER_DATETO}>
				<{$dropend}>
			</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active" href="<{$weeklyLink}>" style="display: inline-block;padding: 15px 15px;"><{$lastweek_text}></a>
			</li>
			<li class="nav-item">
				<a class="nav-link active" href="<{$alltimeLink}>" style="display: inline-block;padding: 15px 15px;"><{$alltime}></a>
			</li>
			<li class="nav-item">
				<a class="nav-link active" href="<{$lastmonthLink}>" style="display: inline-block;padding: 15px 15px;"><{$lastmonth}></a>
			</li>
		</ul>
	<div class="mw-100 p-3 mb-2 bg-danger text-white">
		<h1 class="text-center"><i id="spotifyalltimechartL" class="bi bi-stars"></i><{$title}><i id="spotifyalltimechartR" class="bi bi-stars"></i></h1>
		<p class="text-center"><b><{$subtitle}></b></p>
	</div>
	<table class="table ">
		<thead class="table-dark">
			<tr>
				<{if $weekly == 1}>
					<{if $monthly == true}>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTTHISMONTH}></th>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTLASTMONTH}></th>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTALBUMCOVER}></th>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTARTISTTITLE}></th>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTALBUMNAME}></th>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTRELEASEYEAR}></th>
					<{else}>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTTHISWEEK}></th>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTLASTWEEK}></th>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTALBUMCOVER}></th>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTARTISTTITLE}></th>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTALBUMNAME}></th>
						<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTRELEASEYEAR}></th>
					<{/if}>	
				<{/if}>
				<{if $weekly == 0}>
					<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTTHISWEEK}></th>
					<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTALBUMCOVER}></th>
					<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTARTISTTITLE}></th>
					<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTALBUMNAME}></th>
					<th scope="col"><{$smarty.const._SPOTIFYAPI_CHARTRELEASEYEAR}></th>
				<{/if}>
			</tr>
		</thead>
		<tbody>
			<{section name=i loop=$chart}>
				<tr>
					<th scope="row"><h3><strong><{$chart[i].tw}></strong></h3></th>
					<{if $weekly == 1}>
					<td><small>
							<{if is_int($chart[i].lw)}>
								<{$chart[i].lw}>
									<{if $charttype == 'accumulated' || $charttype == 'classic'}>
										<{$chart[i].dir}>
									<{/if}>
							<{else}>
								<button type="button" class="btn btn-success btn-xs"><{$chart[i].lw}></button>
							<{/if}>
						<small>
						<{if $chart[i].gg == true}>
							<span class = "spotifyapigg" ><{$smarty.const._SPOTIFYAPI_GREATGAIN}></span>
						<{/if}>
					</td>
					<{/if}>

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
<div class="container">
    <div class="row">
        <div class="col-12">
            <table class="table table-image">
                <thead>
                <tr>
                    <th scope="col"># position</th>
                    <th scope="col">Last week</th>
                    <th scope="col">Album cover</th>
                    <th scope="col">Artist - title</th>
                    <th scope="col">Album name</th>
                    <th scope="col">Release year</th>
                </tr>
                </thead>
                <tbody>
                <{section name=i loop=$chart}>
                    <tr>
                        <th scope="row"><{$td[i].pos}></th>
                        <td>Bootstrap 4 CDN and Starter Template</td>
                        <td class="w-25">
                            <img src="https://s3.eu-central-1.amazonaws.com/bootstrapbaymisc/blog/24_days_bootstrap/sheep-3.jpg"
                                 class="img-fluid img-thumbnail" alt="Sheep">
                        </td>

                        <td>Cristina</td>
                        <td>913</td>
                        <td>2.846</td>
                    </tr>
                    <{/section}>
                </tbody>
            </table>
        </div>
    </div>
</div>
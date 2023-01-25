<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP-CRAWLER</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="https://agencyanalytics.com/img/logo/icon.svg" type="image/svg+xml" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="jumbotron" style="padding: 30px; text-align: center;">
                <h2>PHP-CRAWLER FOR AGENCY ANALYTICS</h2>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h3 class="card-title">Pages Crawled</h3>
                        <h4 class="card-text">{{ $pagesCrawled }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h3 class="card-title">Unique Images</h3>
                        <h4 class="card-text">{{ $uniqueImages }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h3 class="card-title">Unique External Links</h3>
                        <h4 class="card-text">{{ $uniqueExternalLinks }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h3 class="card-title">Unique Internal Links</h3>
                        <h4 class="card-text">{{ $uniqueInternalLinks }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h3 class="card-title">Average Page Load</h3>
                        <h4 class="card-text">{{ round($averagePageLoadTime, 3) }} seconds</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h3 class="card-title">Average Word Count</h3>
                        <h4 class="card-text">{{ $averageWordCount }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h3 class="card-title">Average Title Length</h3>
                        <h4 class="card-text">{{ round($averageTitleLength, 3) }}</h4>
                    </div>
                </div>
            </div>
            
        </div>
        <br>
        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Page</th>
                        <th>Status Code</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < count($pages); $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $pages[$i] }}</td>
                            <td>{{ $statusCodes[$i] }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        
    </div>    
    
</body>
</html>
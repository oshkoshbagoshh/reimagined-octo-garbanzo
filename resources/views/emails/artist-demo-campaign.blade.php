<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .header img {
            max-width: 150px;
            border-radius: 50%;
        }
        .content {
            padding: 20px 0;
        }
        .message {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .track {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .track-image {
            width: 60px;
            height: 60px;
            margin-right: 15px;
            background-color: #eee;
        }
        .track-info h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        .track-info p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .show {
            margin-bottom: 20px;
        }
        .show h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        .show p {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4a76a8;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .social-links {
            margin-top: 30px;
            text-align: center;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #4a76a8;
            text-decoration: none;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($artist->profile_image)
                <img src="{{ $artist->profile_image }}" alt="{{ $artist->name }}">
            @endif
            <h1>{{ $artist->name }}</h1>
        </div>
        
        <div class="content">
            <div class="message">
                {!! nl2br(e($message)) !!}
            </div>
            
            @if(count($featuredTracks) > 0)
                <div class="section-title">Featured Tracks</div>
                @foreach($featuredTracks as $track)
                    <div class="track">
                        @if(isset($track->cover_url))
                            <img class="track-image" src="{{ $track->cover_url }}" alt="{{ $track->title }}">
                        @else
                            <div class="track-image"></div>
                        @endif
                        <div class="track-info">
                            <h3>{{ $track->title }}</h3>
                            @if(isset($track->duration))
                                <p>{{ floor($track->duration / 60) }}:{{ str_pad($track->duration % 60, 2, '0', STR_PAD_LEFT) }}</p>
                            @endif
                            @if(isset($track->description))
                                <p>{{ $track->description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                <a href="{{ route('artists.show', $artist->id) }}" class="button">Listen to More</a>
            @endif
            
            @if(count($upcomingShows) > 0)
                <div class="section-title">Upcoming Shows</div>
                @foreach($upcomingShows as $show)
                    <div class="show">
                        <h3>{{ $show->title }}</h3>
                        <p>{{ \Carbon\Carbon::parse($show->date)->format('D, M j, Y \a\t g:i A') }}</p>
                        <p>{{ $show->venue }}, {{ $show->city }}, {{ $show->country }}</p>
                        @if($show->ticket_url)
                            <a href="{{ $show->ticket_url }}" class="button">Get Tickets</a>
                        @endif
                    </div>
                @endforeach
            @endif
            
            @if($artist->website || ($artist->social_links && count($artist->social_links) > 0))
                <div class="social-links">
                    @if($artist->website)
                        <a href="{{ $artist->website }}" target="_blank">Website</a>
                    @endif
                    
                    @if($artist->social_links)
                        @foreach($artist->social_links as $platform => $url)
                            @if($url)
                                <a href="{{ $url }}" target="_blank">{{ ucfirst($platform) }}</a>
                            @endif
                        @endforeach
                    @endif
                </div>
            @endif
        </div>
        
        <div class="footer">
            <p>You received this email because you expressed interest in {{ $artist->name }}.</p>
            <p>&copy; {{ date('Y') }} {{ $artist->name }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
    <channel>
        <title>{{env('APP_NAME')}}</title>
        <description>{{env('INSTANCE_DESCRIPTION')}}</description>
        <lastBuildDate>{{ $posts->first()->created_at->toRfc7231String() }}</lastBuildDate>
        <link>{{env('APP_URL')}}</link>
        <webMaster>{{env('INSTANCE_MAIL')}}</webMaster>
        <generator>Revising Stuffs CMS {{env("APP_VERSION")}}</generator>
        @foreach ($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <description>
                <![CDATA[<img src="{{url('storage/'.$post->curriculum->slug.'/'.$post->level->slug.'/'.$post->course->slug.'/'.$post->id.'-'.$post->slug.'.thumbnail.png')}}" style="width:100%;height:auto"/>
                {{ $post->description }}</br>
                <p>{{__('Course')}} : {{$post->course->name}}</p>
                <p>{{__('Level')}} : {{$post->level->name}}</p>
                <p>{{__('Type')}} : {{$post->type->name}}</p>
                <p>{{__('Curriculum and school')}} : {{$post->curriculum->name}}/{{$post->school->name}}</p>   
                ]]>
            </description>
            <pubDate>{{$post->created_at->toRfc7231String() }}</pubDate>
            <author>{{$post->user->name}}</author>
            <link>{{route('post.public.view', [$post->slug, $post->id])}}</link>
            <guid>{{$post->slug}}-{{$post->id}}</guid>
        </item>
        @endforeach
    </channel>
</rss>
@extends('layouts.master')

@section('style')
    <style>
        body { background-color: green; color: white; }
    </style>
@endsection

@section('script')
    <script type="text/javascript">
        alert("자식 뷰의 'script' 섹션입니다.");
    </script>
@endsection

@section('content')
    <h1><?= isset($greeting) ? "{$greeting} " : 'Hello '; ?><?= $name; ?></h1>
    
    <h1>Example for string interpolation</h1>
    <p>{{ $greeting or 'Hello' }} {{ $name or '' }}</p>

    <h1>Example for comment</h1>
    <!-- HTML 주석 안에서 {{ $name }}을 출력합니다. -->
    {{-- 블레이드 주석 안에서 {{ $name }}을 출력합니다. --}}

    <h1>Example for control statement</h1>
    @if ($itemCount = count($items))
        <p>{{ $itemCount }} 종류의 과일이 있습니다.</p>
    @else
        <p>엥~ 아무것도 없는데요!</p>
    @endif

    <?php /* $items = []; */ ?>
    <h1>Example for repetitive statement</h1>

    <h2>foreach statement</h2>
    <ul>
    @foreach($items as $item)
        <li>{{ $item }}</li>
    @endforeach
    </ul>

    <h2>forelse statement</h2>
    <ul>
        @forelse ($items as $item)
            <li>{{ $item }}</li>
        @empty
            <li>엥~ 아무것도 없는데요!</li>
        @endforelse
    </ul>

    <p>이 페이지는 자식 뷰의 'content' 섹션입니다.</p>

    @include('partials.footer')
@endsection
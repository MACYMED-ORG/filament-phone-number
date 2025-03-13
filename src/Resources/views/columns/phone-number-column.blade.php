@php
    $phoneInfo = $getPhoneInfo($getState());
@endphp

<div class="flex items-center space-x-1">
    @if($phoneInfo && $phoneInfo['country_code'] && $shouldShowFlags())
        <span class="flex-shrink-0">
            <span class="inline-block w-6 h-4 overflow-hidden align-middle">
                {!! $phoneInfo['flag'] !!}
            </span>

        </span>
    @endif

    <span>
        @if($phoneInfo)
            {{ $phoneInfo['number'] }}
        @else
            {{ $getState() }}
        @endif
    </span>
</div>
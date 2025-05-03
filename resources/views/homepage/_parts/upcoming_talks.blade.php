<h3>Upcoming Talks</h3>

@foreach ($upcomingTalks as $upcomingTalk)
    <a href="{{ $upcomingTalk['url'] }}">
        <li style="list-style: none; font-size: 1.25em; line-height: 1.9em" class="mb-4">
            <div class="d-flex" style="justify-content: space-between">
                {{ $upcomingTalk['title'] }}<br>

                <div style="width:5em; justify-content: space-between; white-space: nowrap; color:#BBB;">
                    {{ date("M j, Y", strtotime($upcomingTalk['date'])) }}
                </div>
            </div>

            <div style="color:#BBB; font-size: 0.9em">
                {{ $upcomingTalk['location'] }}
            </div>
        </li>

    </a>
@endforeach

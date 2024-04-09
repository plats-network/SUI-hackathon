@php
  $events = eventImprogess();
@endphp
@if ($events && count($events) > 0)
    <section class="event-improgress">
      <div class="item">
        <h3>My Event Improgress</h3>
        <table>
          @foreach($events as $item)
            <tr>
              <td width="20%" class="img">
                <a href="{{route('job.getTravelGame', ['task_id' => $item->task_id])}}">
                  <img src="{{optional($item->task)->banner_url}}" alt="{{optional($item->task)->name}}">
                </a>
              </td>
              <td width="80%" class="title">
                <a href="{{route('job.getTravelGame', ['task_id' => $item->task_id])}}">
                  {{Str::limit(optional($item->task)->name, 30)}}
                </a> 
              </td>
            </tr>
          @endforeach
        </table>
      </div>
    </section>
@endif
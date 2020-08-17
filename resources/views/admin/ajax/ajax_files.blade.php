@forelse ($files as $item)
<a href="/uploads/project_files/{{$item->project_id}}/{{$item->file_name}}" download class="attachment-box ripple-effect"><span>{{$item->file_name}}</span>
    <p>**{{$item->file_type}} <strong>{{$item->sender_name}}</strong></p>
<i>{{$item->created_at->diffForHumans()}}</i>
</a>
@empty
<div style="text-align:center">
    <h3> No files uploaded</h3>
</div>
@endforelse
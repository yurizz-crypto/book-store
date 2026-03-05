@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
    @if (trim($slot) === 'Laravel' || trim($slot) === 'PageTurner')
        PageTurner<span style="color: #FF8040;">.</span>
    @else
        {!! $slot !!}
    @endif
</a>
</td>
</tr>
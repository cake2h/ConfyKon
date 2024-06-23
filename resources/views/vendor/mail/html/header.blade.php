@props(['url'])
<tr>
<td class="header">
@if (trim($slot) === 'UTMN')
<img src="{{ asset('img/logo2.png') }}" class="logo" alt="utmn logo">
@else
{{ $slot }}
@endif
</td>
</tr>

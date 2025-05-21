@props(['url'])
<tr>
<td class="header">
@if (trim($slot) === 'UTMN')
<img src="{{ asset('img/logo.png') }}" class="logo" alt="logo">
@else
{{ $slot }}
@endif
</td>
</tr>

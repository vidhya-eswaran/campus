@component('mail::message')
# Payment message 

Dear {{$email}},

Your Payment is Successfull.

@component('mail::button', ['url' => 'enter your desired url'])
Blog
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
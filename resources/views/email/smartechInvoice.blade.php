<p>
    Hi Osama, I hope you are doing well :)
</p>
<p>
    Can you please generate the following invoice:
</p>

<p>
    To: {{ str_replace(PHP_EOL,'<br>',$invoice->to) }}<br>
    Currency: {{ str_replace(PHP_EOL,'<br>',$invoice->currency)}}<br>
</p>
<p>
    The invoice should have the following items:<br><br>
    @foreach($invoice->items as $item)
        {{$item['comment']}} - {{$invoice->currency}}{{ $item['amount'] * $item['rate'] }}<br>
    @endforeach
    <br>
    Invoice total is: {{$invoice->currency}}{{ $invoice->total }}
</p>
<p>
    Also add the following note at the bottom:<br>
    {!!  str_replace("\n",'<br>',$invoice->notes) !!}
</p>
Send it to me, and I will forward it to our customer.<br>
<br>
Thanks,<br>
{{ $signature }}

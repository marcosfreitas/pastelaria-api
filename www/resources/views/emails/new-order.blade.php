@component('mail::message')

    # Olá {{ $order['client']->name }}!

    Seu pedido foi recebido e está sendo preparado. Veja abaixo os detalhes do pedido:

    Código do pedido: {{ $order['uuid'] }}

    Itens pedidos:

    @foreach ($order['pastels'] as $item)
    - {{ $item->name }} : {{ $item->price }}
    @endforeach

    Obrigado.
@endcomponent

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-outline']) }}>
    {{ $slot }}
</button>

@props(['name', 'size' => null, 'label' => null])
<i {{ $attributes->class(['bi', 'bi-'.$name, 'sk-icon-'.$size => $size])->merge($label ? ['role' => 'img', 'aria-label' => $label] : ['aria-hidden' => 'true']) }}></i>

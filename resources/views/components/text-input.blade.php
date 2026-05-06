@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-[#F3F4F6] border border-gray-300 text-gray-700 rounded-lg focus:ring-2 focus:ring-[#228B22] focus:border-[#228B22] block w-full p-3 transition placeholder-gray-400 text-sm shadow-sm']) }}>
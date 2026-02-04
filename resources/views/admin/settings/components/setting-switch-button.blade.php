<label
    class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 hover:border-sky-300 transition-all cursor-pointer">
    <div class="flex-1">
        <h4 class="text-base font-semibold text-gray-800 mb-1">{{ $label }}</h4>
        <p class="text-sm text-gray-600">{{ $description }}</p>
    </div>
    <input type="checkbox" class="setting-input w-5 h-5 text-sky-600 cursor-pointer" data-setting-key="{{ $settingKey }}"
        data-setting-type="boolean" data-category-id="{{ $categoryId }}" {{ $checked ? 'checked' : '' }}>
</label>

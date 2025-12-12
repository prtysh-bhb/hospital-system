@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
    <div class="space-y-4">

        @foreach ($settingscategory as $category)
            @php
                $accordionId = 'accordion_' . $category->id;
                $iconId = 'icon_' . $category->id;
                $categorySettings = $Setting->where('setting_category_id', $category->id);
            @endphp

            <div class="border rounded-xl shadow-sm bg-white overflow-hidden">

                {{-- Accordion Button --}}
                <button type="button" onclick="toggleAccordion('{{ $accordionId }}', '{{ $iconId }}')"
                    class="w-full flex justify-between items-center px-5 py-4 bg-gray-100 hover:bg-gray-200 transition">
                    <span class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fa fa-sliders"></i>
                        {{ ucwords(str_replace('_', ' ', $category->name)) }}
                    </span>

                    <span id="{{ $iconId }}" class="text-xl font-bold">+</span>
                </button>

                {{-- Accordion Content --}}
                <div id="{{ $accordionId }}" class="hidden px-5 py-4 bg-white border-t">

                    @if ($categorySettings->count() > 0)
                        {{-- Settings Grid - 4 cards per row --}}
                        <div class="grid lg:grid-cols-4 gap-4 mb-4">
                            @foreach ($categorySettings as $setting)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:shadow-sm transition "
                                    data-category-id="{{ $category->id }}">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 flex justify-between items-center gap-4">

                                        {{-- LABEL TEXT --}}
                                        <span>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</span>

                                        @switch($setting->type)
                                            @case('boolean')
                                                {{-- Toggle Switch for Boolean --}}
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer setting-input"
                                                        data-setting-id="{{ $setting->id }}" data-category-id="{{ $category->id }}"
                                                        data-setting-type="boolean"
                                                        {{ $setting->getRawOriginal('value') == '1' ? 'checked' : '' }}>

                                                    <div
                                                        class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-sky-300 rounded-full
                           peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full
                           peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px]
                           after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all
                           peer-checked:bg-sky-600">
                                                    </div>
                                                </label>
                                            @break

                                            @case('integer')
                                                {{-- Number Input for Integer --}}
                                                <input type="number"
                                                    class="setting-input w-24 border border-gray-300 rounded-lg px-3 py-2 text-center
                       focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                                                    data-setting-id="{{ $setting->id }}" data-category-id="{{ $category->id }}"
                                                    data-setting-type="integer" value="{{ $setting->getRawOriginal('value') }}"
                                                    min="0">
                                            @break

                                            @case('json')
                                                {{-- JSON Textarea --}}
                                                <textarea
                                                    class="setting-input w-64 border border-gray-300 rounded-lg px-3 py-2 h-20 text-sm font-mono
                       focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                                                    data-setting-id="{{ $setting->id }}" data-category-id="{{ $category->id }}" data-setting-type="json"
                                                    placeholder="Enter valid JSON">{{ $setting->getRawOriginal('value') }}</textarea>
                                            @break

                                            @default
                                                {{-- String Input --}}
                                                <input type="text"
                                                    class="setting-input w-64 border border-gray-300 rounded-lg px-3 py-2
                       focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                                                    data-setting-id="{{ $setting->id }}" data-category-id="{{ $category->id }}"
                                                    data-setting-type="string" value="{{ $setting->getRawOriginal('value') }}">
                                        @endswitch
                                    </label>


                                </div>
                            @endforeach
                        </div>

                        {{-- Single Save Button for this Category --}}
                        <div class="flex justify-end pt-4 border-gray-200 p-4 ">
                            <button type="button"
                                class="save-category-btn px-6 py-2 bg-sky-600 text-white text-sm font-medium rounded-lg hover:bg-sky-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                                data-category-id="{{ $category->id }}">
                                <i class="fas fa-save"></i> Save {{ ucwords(str_replace('_', ' ', $category->name)) }}
                            </button>
                        </div>
                    @else
                        <p class="text-gray-500">No settings found for this category.</p>
                    @endif

                </div>
            </div>
        @endforeach

    </div>
@endsection

@push('scripts')
    <script>
        function toggleAccordion(contentId, iconId) {
            const allContents = document.querySelectorAll('[id^="accordion_"]');

            allContents.forEach((content) => {
                const cid = content.getAttribute('id');
                const icon = document.querySelector(`#icon_${cid.replace('accordion_', '')}`);

                if (cid === contentId) {
                    // Toggle selected accordion
                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        content.style.maxHeight = content.scrollHeight + "px";
                        icon.textContent = '-';
                    } else {
                        content.style.maxHeight = "0px";
                        setTimeout(() => content.classList.add('hidden'), 200);
                        icon.textContent = '+';
                    }
                } else {
                    // Close all other accordions
                    content.style.maxHeight = "0px";
                    setTimeout(() => content.classList.add('hidden'), 200);
                    icon.textContent = '+';
                }
            });
        }

        // Handle boolean toggle text update
        $(document).on('change', '.setting-input[data-setting-type="boolean"]', function() {
            const $label = $(this).closest('label').find('.status-text');
            $label.text(this.checked ? 'Enabled' : 'Disabled');
        });

        // Save all settings in a category
        $(document).on('click', '.save-category-btn', function() {
            const $btn = $(this);
            const categoryId = $btn.data('category-id');
            const $inputs = $(`.setting-input[data-category-id="${categoryId}"]`);

            // Collect all settings data for this category
            const settings = [];
            let hasError = false;

            $inputs.each(function() {
                const $input = $(this);
                const settingId = $input.data('setting-id');
                const settingType = $input.data('setting-type');

                let value;
                if (settingType === 'boolean') {
                    value = $input.is(':checked') ? '1' : '0';
                } else {
                    value = $input.val();
                }

                // Validate JSON if type is json
                if (settingType === 'json' && value) {
                    try {
                        JSON.parse(value);
                    } catch (e) {
                        toastr.error('Invalid JSON format in one of the fields');
                        hasError = true;
                        return false;
                    }
                }

                settings.push({
                    setting_id: settingId,
                    value: value
                });
            });

            if (hasError) return;

            const originalText = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: '{{ route('admin.settings.update') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    settings: settings
                },
                success: function(response) {
                    $btn.prop('disabled', false).html(originalText);
                    if (response.success) {
                        toastr.success(response.message || 'Settings updated successfully');
                        // Flash effect on save
                        $inputs.each(function() {
                            $(this).closest('.bg-gray-50').addClass(
                                'bg-green-50 border-green-300');
                            setTimeout(() => {
                                $(this).closest('.bg-green-50').removeClass(
                                    'bg-green-50 border-green-300').addClass(
                                    'bg-gray-50');
                            }, 1000);
                        });
                    } else {
                        toastr.error(response.message || 'Failed to update settings');
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(originalText);
                    const errorMsg = xhr.responseJSON?.message || 'Server Error. Try again.';
                    toastr.error(errorMsg);
                }
            });
        });
    </script>
@endpush

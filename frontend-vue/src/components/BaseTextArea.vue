<script setup>
defineProps({
  id: { type: String, required: true },
  label: { type: String, default: "" },
  placeholder: { type: String, default: "" },
  modelValue: [String, Number],
  rows: { type: Number, required: ''},
  cols: { type: Number, required: ''},

});

defineEmits(["update:modelValue", "blur"]);
</script>

<template>
  <div>
    <label :for="id" class="block mb-2 text-sm font-medium text-gray-900">
      {{ label }}
    </label>
    <textarea
      :id="id"
      :placeholder="placeholder"
      class="w-full p-2 border border-gray-300 rounded"
      :rows="rows" 
      :cols="cols"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      @blur="$emit('blur', $event)"
    ></textarea>
  </div>
</template>

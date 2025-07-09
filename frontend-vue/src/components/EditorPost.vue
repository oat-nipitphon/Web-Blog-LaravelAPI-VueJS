<template>
  <div class="w-full m-auto p-5 rounded-lg">
    <Editor v-model="value" editorStyle="height: 320px" />
  </div>
  <Form v-slot="$form" :resolver="resolver" :initialValues="initialValues" @submit="onFormSubmit" class="flex flex-col gap-4">
    <div class="flex flex-col gap-1">
        <Editor name="content" editorStyle="height: 320px" />
        <Message v-if="$form.content?.invalid" severity="error" size="small" variant="simple">{{ $form.content.error?.message }}</Message>
    </div>
    <Button type="submit" severity="secondary" label="Submit" />
</Form>
<Editor v-model="value" editorStyle="height: 320px">
    <template v-slot:toolbar>
        <span class="ql-formats">
            <button v-tooltip.bottom="'Bold'" class="ql-bold"></button>
            <button v-tooltip.bottom="'Italic'" class="ql-italic"></button>
            <button v-tooltip.bottom="'Underline'" class="ql-underline"></button>
        </span>
    </template>
</Editor>
</template>
<script setup>
// import Editor from 'primevue/editor';
import Editor from "quill/core/editor";
</script>

<!-- Page Template Example
@component: show-welcome
@template
@script
-->

<template>
    <div id='vue-home'>
		<div class='spacer-2'></div>
		<show-message message="Welcome to Caligrafy"></show-message>
		<div class='spacer-2'></div>
    </div>
</template>

<script>
import ShowMessage from '@/components/global/ShowMessage.vue';

export default {
    name: 'vue-home',
    components: {
        'show-message': ShowMessage
    },
	data() {
		return {}
	},
	methods: {
		// define methods
	},
	computed: {
		// computed variables
	},
	
	mounted() {
		// execute methods and actions upon mounting
	},
	watch: {
		// watched properties
	}
};
</script>
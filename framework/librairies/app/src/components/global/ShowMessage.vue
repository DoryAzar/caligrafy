<!-- Component Template -->

<template>
	<div class='horizontal-center'>
		{{ message }}
	</div>
</template>

<script>
export default {
    name: 'show-message',
    props: ['message']

};
</script>
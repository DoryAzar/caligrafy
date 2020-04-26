<!-- Show Navigation Component
Global component responsible for rendering the global navigation of the application
-->

<template>
    <header class='color0'>
		<nav class='horizontal'>
			<div class="spacer-1"></div>
			<h1 class='large-text horizontal-center'>
				<router-link :to='{ name: "home" }'>
					<span class='semibold'>Cali</span>
					<span>grafy</span>
				</router-link>
			</h1>
		
		</nav>
	</header>
</template>

<script>
export default {
    name: 'show-navigation'
};
</script>
<script lang="ts">
    import '@skeletonlabs/skeleton/themes/theme-crimson.css';
    import '@skeletonlabs/skeleton/styles/skeleton.css';
    import {AppShell, AppRail, AppRailTile, AppRailAnchor} from '@skeletonlabs/skeleton';
	import '../app.postcss';
    import FaSolidBurger from "$lib/icons/FaSolidBurger.svelte";
    import { LightSwitch } from '@skeletonlabs/skeleton';
    import FaSolidSettings from "$lib/icons/FaSolidSettings.svelte";

    let pageSelected: string = '/';
</script>


<AppShell>
    <svelte:fragment slot="sidebarLeft">
        <AppRail>
            <AppRailTile bind:group={pageSelected} name="tile-1" value={0} title="tile-1">
                <svelte:fragment slot="lead">(icon)</svelte:fragment>
                <span>Tile 1</span>
            </AppRailTile>
            <AppRailTile bind:group={pageSelected} name="tile-2" value={1} title="tile-2">
                <svelte:fragment slot="lead">(icon)</svelte:fragment>
                <span>Tile 2</span>
            </AppRailTile>
            <AppRailTile bind:group={pageSelected} name="tile-3" value={2} title="tile-3">
                <svelte:fragment slot="lead">(icon)</svelte:fragment>
                <span>Tile 3</span>
            </AppRailTile>
            <!-- --- -->
            <svelte:fragment slot="trail">
                <AppRailAnchor href="/" title="Settings">
                    <svelte:fragment slot="lead" >
                        <div style="width: 30%;">
                            <FaSolidSettings/>
                        </div>
                    </svelte:fragment>
                    <span>Settings</span>
                </AppRailAnchor>
            </svelte:fragment>
        </AppRail>
    </svelte:fragment>
    <slot />
</AppShell>


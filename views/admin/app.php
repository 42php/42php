<md-layout id="app" md-column>
    <md-toolbar>
        <h2 class="md-title" style="flex: 1">{{ app.state.title }}</h2>

        <md-button class="md-icon-button" v-if="app.state.user.id">
            <md-icon>power_settings_new</md-icon>
        </md-button>
    </md-toolbar>
    <md-layout style="flex: 1; height: 100%;" v-if="app.state.user.data !== null">

        <md-list class="md-dense" style="width:300px; overflow: auto;">
            <?= \Core\View::partial('admin/menu') ?>
        </md-list>

        <div id="app-content" style="flex: 1; overflow: auto;">
            <p v-if="app.state.user.data === false">
                Pas connecté
            </p>
            <p v-if="app.state.user.id">
                Connecté
            </p>
        </div>

    </md-layout>
</md-layout>
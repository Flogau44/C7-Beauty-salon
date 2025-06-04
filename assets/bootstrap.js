import { startStimulusApp } from "@symfony/stimulus-bridge";

// Enregistre les contrôleurs Stimulus depuis controllers.json et le dossier controllers/
export const app = startStimulusApp(
    require.context(
        "@symfony/stimulus-bridge/lazy-controller-loader!./controllers",
        true,
        /\.[jt]sx?$/
    )
);

// Enregistre des contrôleurs personnalisés ou tiers ici
// app.register('some_controller_name', SomeImportedController);

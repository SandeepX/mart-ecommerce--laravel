import store from '@/store/index';

function registerStoreModule(modulePath, module) {
    console.log(store, 'store');
    if (store.hasModule(modulePath)) {
        return '';
    }

    store.registerModule(modulePath, module);
}

function unregisterStoreModule(modulePath) {
    if (store.hasModule(modulePath)) {
        store.unregisterModule(modulePath);
    }
}

export {
    registerStoreModule,
    unregisterStoreModule
}

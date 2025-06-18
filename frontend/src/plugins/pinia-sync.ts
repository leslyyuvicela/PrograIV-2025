type StoreMap = Record<string, () => any>

/**
 * Escucha los cambios en localStorage y actualiza el store correspondiente.
 */
export function setupStoreSync(stores: StoreMap) {
  window.addEventListener('storage', (event) => {
    const storeKey = event.key
    if (!storeKey || !(storeKey in stores)) return

    try {
      const newState = JSON.parse(event.newValue ?? '{}')
      const useStore = stores[storeKey]
      const store = useStore()

      if (store && newState) {
        store.$patch(newState)
      }
    } catch (error) {
      console.warn(`Error al sincronizar el store "${storeKey}":`, error)
    }
  })
}
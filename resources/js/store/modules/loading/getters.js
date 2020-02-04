export const getMessage = state => state.message;
export const getProgressColor = state => state.progressColor;
export const isActiveOverlay = state => 0 < Object.keys(state.overlayProcesses).length;
export const isLoading = state => 0 < Object.keys(state.processes).length;

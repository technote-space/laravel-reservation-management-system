export const getMessage = state => state.message;
export const getProgressColor = state => state.progressColor;
export const isActiveOverlay = state => Object.keys(state.overlayProcesses).length > 0;
export const isLoading = state => key => state.processes[ key ] === true;

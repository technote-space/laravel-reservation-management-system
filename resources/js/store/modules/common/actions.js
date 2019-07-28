import { SET_DRAWER_ACTIVE, SET_DRAWER_STATE, SET_TOOLBAR_ACTIVE } from './constant';

export const setDrawerOpen = (context, flag) => context.commit(SET_DRAWER_STATE, { flag });
export const switchDrawerOpen = context => setDrawerOpen(context, !context.state.isOpenDrawer);
export const openDrawer = context => setDrawerOpen(context, true);
export const closeDrawer = context => setDrawerOpen(context, false);

export const setDrawerActive = (context, flag) => context.commit(SET_DRAWER_ACTIVE, { flag });
export const switchDrawerActive = context => setDrawerActive(context, !context.state.isActiveDrawer);
export const onDrawer = context => setDrawerActive(context, true);
export const offDrawer = context => setDrawerActive(context, false);

export const setToolbarState = (context, flag) => context.commit(SET_TOOLBAR_ACTIVE, { flag });
export const switchToolbarState = context => setToolbarState(context, !context.state.isActiveToolbar);
export const onToolbar = context => setToolbarState(context, true);
export const offToolbar = context => setToolbarState(context, false);

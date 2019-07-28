import { LOCK_PROCESS, UNLOCK_PROCESS } from './constant';

export const onLoading = (context, key) => context.commit(LOCK_PROCESS, { key: key, overlay: true });
export const onLoadingSilently = (context, key) => context.commit(LOCK_PROCESS, { key: key, overlay: false });
export const offLoading = (context, key) => context.commit(UNLOCK_PROCESS, { key: key });

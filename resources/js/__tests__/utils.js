import flushPromises from 'flush-promises';

export async function flush () {
    await flushPromises();
    jest.runAllTimers();
}

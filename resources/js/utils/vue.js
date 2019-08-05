import Vue from 'vue';
import { createLocalVue } from '@vue/test-utils';

export default 'test' === process.env.NODE_ENV ? createLocalVue() : Vue;

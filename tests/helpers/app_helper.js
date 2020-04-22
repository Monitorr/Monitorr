import supertest from 'supertest';
import { app } from '../../index';

export default class Helper {
    constructor(model) {
        this.api = supertest(app);
    }
}

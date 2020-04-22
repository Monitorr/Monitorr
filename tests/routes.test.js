import App from './helpers/app_helper.js';
const helper = new App();

describe('Ping Endpoint', () => {
  it('should get all pings', async () => {
    const res = await helper.api.get('/ping');
    expect(res.statusCode).toEqual(200);
    expect(res.body).toHaveLength(3);
  });
});

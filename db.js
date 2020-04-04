import Influx from 'influx';

export const influx = new Influx.InfluxDB({
  host: "localhost",
  database: "monitorr",
  schema: [
    {
      measurement: 'ping',
      fields: {
        ping: Influx.FieldType.INTEGER,
        online: Influx.FieldType.BOOLEAN
      },
      tags: ['id', 'url']
    }
  ]
});

influx.getDatabaseNames()
  .then(names => {
    if (!names.includes('monitorr')) {
      return influx.createDatabase('monitorr');
    }
  });

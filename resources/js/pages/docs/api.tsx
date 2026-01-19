import { Head } from '@inertiajs/react';
import SwaggerUI from 'swagger-ui-react';
import 'swagger-ui-react/swagger-ui.css';

export default function ApiDocs() {
  return (
    <>
      <Head title="API documentation" />
      <SwaggerUI url="/api/docs" />
    </>
  );
}

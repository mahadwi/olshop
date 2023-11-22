import React from 'react';
import { Counter } from './features/counter/Counter';
import Index from './containers/register/index';
import IndexNavbar from './components/navbar/IndexNavbar';

function App() {
  return (
    <>
    <IndexNavbar/>
    <Index/>
    </>
  );
}

export default App;

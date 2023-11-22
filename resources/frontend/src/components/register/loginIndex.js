import React, { Component } from 'react'
import './loginIndex.css'
import { Button } from "react-bootstrap";

export default class loginIndex extends Component {
  render() {
    return (
      <div className='box h-100 d-flex align-items-center justify-content-center'>
      <form>
      <br />
      <br />
      <div className='or'>
          <h6>-OR-</h6>
      </div>
      <div className="d-grid gap-2">
        <input type="text" className='inp' placeholder='Fullname'/>
      </div>
      <div className="d-grid gap-2">
        <input type="text" className='inp' placeholder='Email Address'/>
      </div>
     <div className="d-grid gap-2">
     <input type="password" className='inp' placeholder='Password'/>
      </div>
      <div className="d-grid gap-2">
     <input type="password" className='inp' placeholder='Confirm Password'/>
      </div>
      <br />
      <input type='checkbox'/>
      <span className='ppcp'>By clicking Register, you agree to our Terms, Privacy Policy and Cookie Policy.</span>
      <br/>
      <br/>
      <div className="d-grid gap-2">
       <Button size='md'>Create Account</Button>
      </div>
       <br/>
       <div className='ppcp'>
       <span>Already have an account?</span><a href='#'> Log in</a>
       </div>
      </form>
  </div>
    )
  }
}

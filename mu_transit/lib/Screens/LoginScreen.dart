import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController grNoController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  bool isPasswordVisible = false; // Toggle state for password visibility

  // Form key for validation
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();

  // Function to handle login
  void handleLogin() {
    if (_formKey.currentState!.validate()) {
      // If validation passes, proceed with login action
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Login Successful')));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white, // Set background to white
      body: Center(
        child: SingleChildScrollView(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 32.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                // University Logo
                Image.asset(
                  'assets/mu_logo.png', // Ensure this file exists in your assets folder
                  height: 100,
                ),
                const SizedBox(height: 10),

                // App Name - "MU Transit" with updated color
                Text(
                  "MU Transit",
                  style: GoogleFonts.poppins(
                    fontSize: 24,
                    fontWeight: FontWeight.bold, // Bold font
                    color: const Color(0xFF008D62), // Updated color
                    letterSpacing: 1.2,
                  ),
                ),
                const SizedBox(height: 20),

                // Login Form Container with FormKey for validation
                Form(
                  key: _formKey,
                  child: Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black12,
                          blurRadius: 10,
                          spreadRadius: 2,
                          offset: const Offset(0, 5),
                        ),
                      ],
                    ),
                    child: Column(
                      children: [
                        Text(
                          "Login",
                          style: GoogleFonts.poppins(
                            fontSize: 26,
                            fontWeight: FontWeight.bold, // Bold font
                            color: Colors.black,
                          ),
                        ),
                        const SizedBox(height: 20),

                        // GR No. Input with validation
                        TextFormField(
                          controller: grNoController,
                          decoration: InputDecoration(
                            labelText: "GR No.",
                            labelStyle: GoogleFonts.poppins(
                              color: Colors.black87,
                              fontWeight: FontWeight.bold,
                            ),
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                            prefixIcon: const Icon(Icons.person, color: Colors.black87),
                          ),
                          keyboardType: TextInputType.number,
                          validator: (value) {
                            // GR No. validation (must be numbers, not empty, and minimum length of 6)
                            if (value == null || value.isEmpty) {
                              return "GR No. is required";
                            } else if (!RegExp(r'^[0-9]+$').hasMatch(value)) {
                              return "GR No. must be a number";
                            } else if (value.length < 6) {
                              return "GR No. must be at least 6 characters long";
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: 20),

                        // Password Input with Eye Icon and validation
                        TextFormField(
                          controller: passwordController,
                          obscureText: !isPasswordVisible,
                          decoration: InputDecoration(
                            labelText: "Password",
                            labelStyle: GoogleFonts.poppins(
                              color: Colors.black87,
                              fontWeight: FontWeight.bold,
                            ),
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                            prefixIcon: const Icon(Icons.lock, color: Colors.black87),
                            suffixIcon: IconButton(
                              icon: Icon(
                                isPasswordVisible ? Icons.visibility : Icons.visibility_off,
                                color: Colors.grey,
                              ),
                              onPressed: () {
                                setState(() {
                                  isPasswordVisible = !isPasswordVisible;
                                });
                              },
                            ),
                          ),
                          validator: (value) {
                            // Password validation (must be at least 6 characters and contain only letters, numbers, and special symbols)
                            if (value == null || value.isEmpty) {
                              return "Password is required";
                            } else if (value.length < 6) {
                              return "Password must be at least 6 characters long";
                            } else if (!RegExp(r'^[a-zA-Z0-9!@#$%^&*(),.?":{}|<>]+$').hasMatch(value)) {
                              return "Password can only contain letters, numbers, and special symbols";
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: 30),

                        // GO! Button with updated color
                        SizedBox(
                          width: double.infinity,
                          height: 50,
                          child: ElevatedButton(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: const Color(0xFF008D62), // Green color for button
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(25),
                              ),
                            ),
                            onPressed: handleLogin, // Trigger login validation
                            child: Text(
                              "GO!",
                              style: GoogleFonts.poppins(
                                fontSize: 20,
                                fontWeight: FontWeight.bold, // Bold font
                                color: Colors.white,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 20),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

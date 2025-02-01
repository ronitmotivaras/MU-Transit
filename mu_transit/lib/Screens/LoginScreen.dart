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

  // Focus nodes for the text fields to manage hover effects
  final FocusNode grNoFocusNode = FocusNode();
  final FocusNode passwordFocusNode = FocusNode();

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

                // Login Form Container
                Container(
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

                      // GR No. Input with hover effect
                      TextField(
                        controller: grNoController,
                        focusNode: grNoFocusNode, // Attach the focus node
                        decoration: InputDecoration(
                          labelText: "GR No.",
                          labelStyle: GoogleFonts.poppins(
                            color: Colors.black87,
                            fontWeight: FontWeight.bold, // Bold font
                          ),
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                          prefixIcon: const Icon(Icons.person, color: Colors.black87),
                          focusedBorder: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                            borderSide: const BorderSide(
                              color: Color(0xFF008D62), // Green border on focus/hover
                              width: 2,
                            ),
                          ),
                        ),
                        keyboardType: TextInputType.number,
                      ),
                      const SizedBox(height: 20),

                      // Password Input with Eye Icon and hover effect
                      TextField(
                        controller: passwordController,
                        focusNode: passwordFocusNode, // Attach the focus node
                        obscureText: !isPasswordVisible, // Hide/show password
                        decoration: InputDecoration(
                          labelText: "Password",
                          labelStyle: GoogleFonts.poppins(
                            color: Colors.black87,
                            fontWeight: FontWeight.bold, // Bold font
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
                                isPasswordVisible = !isPasswordVisible; // Toggle state
                              });
                            },
                          ),
                          focusedBorder: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                            borderSide: const BorderSide(
                              color: Color(0xFF008D62), // Green border on focus/hover
                              width: 2,
                            ),
                          ),
                        ),
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
                          onPressed: () {
                            // Handle login action
                          },
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
                const SizedBox(height: 20),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
